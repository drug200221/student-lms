import { Component, computed, DestroyRef, effect, inject } from '@angular/core';
import { takeUntilDestroyed, toSignal } from '@angular/core/rxjs-interop';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatButton } from '@angular/material/button';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { toDoc, toHTML, Validators as NgxValidators } from 'ngx-editor';
import { NgxEditorComponent } from '../../../../../shared/components/ngx-editor/ngx-editor';
import { customNgxSchema } from '../../../../../shared/components/ngx-editor/ngx-editor-schema';
import { ContentState } from '../../../../../shared/states/content-state';
import { maxBytesValidator } from '../../../../../shared/validators/maxBytesValidator';
import DOMPurify from 'dompurify';
@Component({
  imports: [
    MatFormField,
    MatLabel,
    MatInput,
    NgxEditorComponent,
    MatButton,
    ReactiveFormsModule,
  ],
  selector: 'psk-edit-content',
  standalone: true,
  styles: ``,
  template: `
    <h3>Редактирование контента</h3>
    <br>
    <form
      [formGroup]="form"
      (ngSubmit)="save()"
      style="display: grid; grid-template-rows: auto 1fr auto;">
      <mat-form-field>
        <mat-label>Название</mat-label>
        <input formControlName="title" matInput>
      </mat-form-field>
      <psk-ngx-editor formControlName="content"></psk-ngx-editor>
      <br>
      <button style="justify-self: start;" matButton="filled" type="submit" [disabled]="isSubmitDisabled()">
        Сохранить
      </button>
    </form>
  `,
})
export class EditContent {
  private readonly destroyRef = inject(DestroyRef);
  private contentState = inject(ContentState);

  public readonly form = new FormGroup({
    title: new FormControl('', { nonNullable: true, validators: [Validators.required] }),
    content: new FormControl<Record<string, unknown>>(toDoc('', customNgxSchema), { nonNullable: true, validators: [NgxValidators.required(), maxBytesValidator()] }),
  });

  constructor() {
    effect(() => {
      const result = this.contentState.data.value()?.result;

      if (result) {
        const decodedContent = this.decodeHtml(result.content ?? '');
        this.form.patchValue({
          title: result.title ?? '',
          content: toDoc(decodedContent, customNgxSchema),
        }, { emitEvent: false });
      }
    });
  }

  private decodeHtml(html: string): string {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const decoded = doc.documentElement.textContent || "";
    return DOMPurify.sanitize(decoded);
  }

  private readonly formStatus = toSignal(this.form.statusChanges, { initialValue: 'INVALID' });

  public readonly isSubmitDisabled = computed(() => {
    return this.formStatus() === 'INVALID';
  });

  public save() {
    if (this.form.valid) {
      const currentFullObject = this.contentState.data.value()?.result;
      const formValues = this.form.getRawValue();

      const payload = {
        courseId: currentFullObject!.courseId,
        title: formValues.title,
        content: toHTML(formValues.content, customNgxSchema),
      };

      this.contentState.save(payload)
        .pipe(takeUntilDestroyed(this.destroyRef))
        .subscribe({
          next: (res) => {
            console.log('Сохранено успешно', res.result);
          },
          error: (err) => {
            console.error('Ошибка сохранения:', err.message);
          },
        });
    }
  }
}
