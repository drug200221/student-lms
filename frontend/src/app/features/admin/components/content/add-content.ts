import { Component, computed, inject } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatButton } from '@angular/material/button';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute, Router } from '@angular/router';
import { toHTML, Validators as NgxValidators } from 'ngx-editor';
import { merge, take } from 'rxjs';
import { NgxEditorComponent } from '../../../../shared/components/ngx-editor/ngx-editor';
import { ContentState } from '../../../../shared/states/content-state';
import { CourseState } from '../../../../shared/states/course-state';
import { maxBytesValidator } from '../../../../shared/validators/maxBytesValidator';
import { MarkdownConverterService } from '../../../../shared/components/ngx-editor/markdown-converter.service';
import customNgxSchema from '../../../../shared/components/ngx-editor/ngx-editor-schema';
import { decodeHtmlEntities } from '../../../../shared/helpers/decodeHtmlEntities';
import { IContent } from '../../../../core/models/content';

@Component({
  imports: [
    MatFormField,
    MatLabel,
    MatInput,
    NgxEditorComponent,
    MatButton,
    ReactiveFormsModule,
  ],
  selector: 'psk-add-content',
  standalone: true,
  styles: ``,
  template: `
    <h3>Добавление контента</h3>
    <br>
    <form
      [formGroup]="form"
      (ngSubmit)="save()"
      style="display: grid; grid-template-rows: auto 1fr auto; width: 100%; min-width: 0;">
      <mat-form-field>
        <mat-label>Название</mat-label>
        <input formControlName="title" matInput>
      </mat-form-field>
      <psk-ngx-editor formControlName="content" [required]="true" style="display: block; width: 100%; overflow: hidden;"></psk-ngx-editor>
      <br>
      <button style="justify-self: start;" matButton="filled" type="submit" [disabled]="isSubmitDisabled()">
        Сохранить
      </button>
    </form>
  `,
})
export class AddContent {
  private _router = inject(Router);
  private _route = inject(ActivatedRoute);
  private _courseState = inject(CourseState);
  private _contentState = inject(ContentState);
  private _markdownConverterService = inject(MarkdownConverterService);
  private _snackBar = inject(MatSnackBar);

  public readonly form = new FormGroup({
    title: new FormControl('', { nonNullable: true, validators: [Validators.required] }),
    content: new FormControl<string | Record<string, unknown>>('', {
      nonNullable: true, validators: [NgxValidators.required(), maxBytesValidator()],
    }),
  });

  private readonly formUpdates = toSignal(
    merge(this.form.valueChanges, this.form.statusChanges),
    { initialValue: null }
  );

  public readonly isSubmitDisabled = computed(() => {
    this.formUpdates();
    return !this.form.dirty || this.form.invalid;
  });

  public save() {
    if (this.form.valid) {
      const courseObject = this._courseState.data.value()?.result;
      const parentId =  this._route.snapshot.queryParamMap.get('parentId')!;

      const formValues = this.form.getRawValue();
      const content = typeof formValues.content === 'string'
        ? this._markdownConverterService.toMarkdown(decodeHtmlEntities(formValues.content))
        : this._markdownConverterService.toMarkdown(toHTML(formValues.content, customNgxSchema));

      console.log(content);
      const payload: Partial<IContent> = {
        courseId: courseObject!.id,
        parentId: parseInt(parentId),
        title: formValues.title,
        content: content,
      };

      this._contentState.save(payload)
        .pipe(take(1))
        .subscribe({
          next: (content) => {
            this._snackBar.open("Успешно сохранено!", 'Скрыть', {
              duration: 5000,
              horizontalPosition: 'right',
              verticalPosition: 'bottom',
              panelClass: ['primary-snackbar'],
            });
            this._router.navigate(['./', content.result?.id], { relativeTo: this._route }).then();
          },
          error: (err) => {
            this._snackBar.open(`Ошибка сохранения: ${err.code}`, 'Скрыть', {
              duration: 5000,
              horizontalPosition: 'right',
              verticalPosition: 'bottom',
              panelClass: ['error-snackbar'],
            });
          },
        });
    }
  }
}
