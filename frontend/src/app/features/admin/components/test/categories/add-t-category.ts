import { Component, computed, inject } from '@angular/core';
import { AbstractControl, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatError, MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { MatButton } from '@angular/material/button';
import { TestCategoriesState } from '../../../../../shared/states/test-categories-state';
import { first } from 'rxjs';

@Component({
  imports: [
    ReactiveFormsModule,
    MatFormField,
    MatInput,
    MatButton,
    MatLabel,
    MatError,
  ],
  selector: 'psk-add-t-category',
  standalone: true,
  styles: ``,
  template: `
    <h3>Добавление категории вопросов</h3>
    <br>
    <form
      (ngSubmit)="save()"
      [formGroup]="form" class="form"
      style="display: grid; grid-template-rows: auto 1fr auto; width: 100%; min-width: 0;">
      <mat-form-field>
        <mat-label>Название</mat-label>
        <input formControlName="title" matInput>
        @if (form.controls.title.hasError('exists')) {
          <mat-error>Категория с таким названием уже существует!</mat-error>
        }
      </mat-form-field>
      <br>
      <button style="justify-self: start;" matButton="filled" type="submit" [disabled]="isSubmitDisabled()">
        Сохранить
      </button>
    </form>
  `,
})
export class AddTCategory {
  protected readonly testCategoriesState = inject(TestCategoriesState);

  public readonly form = new FormGroup({
    title: new FormControl('', {
      nonNullable: true,
      validators: [Validators.required, this.uniqueTitleValidator.bind(this)],
    }),
  });

  public readonly isSubmitDisabled = computed(() => {
    return !this.form.dirty || this.form.invalid;
  });

  protected save() {
    if (this.form.invalid) {
      return;
    }

    const payload = this.form.getRawValue();

    this.testCategoriesState.save(null, payload)
      .pipe(first())
      .subscribe({
        next: () => {
          this.form.reset();
          this.form.markAsPristine();
        },
      });
  }

  private uniqueTitleValidator(control: AbstractControl) {
    const value = control.value?.trim().toLowerCase();
    if (!value) {
      return null;
    }

    const categories = this.testCategoriesState.data.value()?.result ?? [];

    const exists = categories.some(cat => cat.title.toLowerCase() === value);
    return exists ? { exists: true } : null;
  }
}
