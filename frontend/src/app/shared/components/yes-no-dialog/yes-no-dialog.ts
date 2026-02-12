import { Component, inject } from '@angular/core';
import {
  MAT_DIALOG_DATA,
  MatDialogActions, MatDialogClose,
  MatDialogContent,
  MatDialogRef,
  MatDialogTitle
} from '@angular/material/dialog';
import { MatButton } from '@angular/material/button';

@Component({
  imports: [
    MatDialogContent,
    MatDialogActions,
    MatDialogTitle,
    MatButton,
    MatDialogClose,
  ],
  selector: 'psk-yes-no-dialog',
  standalone: true,
  styles: ``,
  template: `
    <h2 matDialogTitle>Подтвердите действие</h2>
    <mat-dialog-content>
      {{ data.info }}
    </mat-dialog-content>
    <mat-dialog-actions>
      <button matButton matDialogClose>Нет</button>
      <button class="error" (click)="onYes()" matButton matDialogClose>Да</button>
    </mat-dialog-actions>
  `,
})
export class YesNoDialog {
  protected data: { info: string } = inject(MAT_DIALOG_DATA);
  private readonly dialogRef = inject(MatDialogRef<YesNoDialog>);

  protected onYes(): void {
    this.dialogRef.close(true);
  }
}
