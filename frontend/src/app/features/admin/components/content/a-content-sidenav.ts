import { Component, computed, inject } from '@angular/core';
import { MatCard, MatCardActions } from '@angular/material/card';
import { MatIcon } from '@angular/material/icon';
import { MatButton, MatIconButton } from '@angular/material/button';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { YesNoDialog } from '../../../../shared/components/yes-no-dialog/yes-no-dialog';
import { finalize, take } from 'rxjs';
import { ContentState } from '../../../../shared/states/content-state';

@Component({
  imports: [
    MatCard,
    MatCardActions,
    MatIcon,
    MatIconButton,
    RouterLink,
    MatButton,
  ],
  selector: 'psk-a-content-sidenav',
  standalone: true,
  styles: ``,
  template: `
    <mat-card style="height: fit-content; margin: 0 .5rem;">
      <mat-card-actions style="flex-direction: column; gap: 0.5rem">
        <section style="display: flex; justify-content: space-around; width: 100%">
          <button class="primary" matIconButton routerLink="edit">
            <mat-icon>edit</mat-icon>
          </button>
          <button matIconButton class="error" (click)="openDialog()">
            <mat-icon>delete</mat-icon>
          </button>
        </section>
        <button [queryParams]="{ parentId: content()?.parentId }" matButton routerLink="../add">
          <mat-icon>add</mat-icon>
          Соседний раздел
        </button>
        <button [queryParams]="{ parentId: content()?.id }" matButton routerLink="../add">
          <mat-icon>add</mat-icon>
          Подраздел
        </button>
      </mat-card-actions>
    </mat-card>
  `,
})
export class AContentSidenav {
  public readonly content = computed(() => this._contentState.data.value()?.result);

  private _router = inject(Router);
  private _route = inject(ActivatedRoute);
  private _contentState = inject(ContentState);
  private readonly _dialog = inject(MatDialog);
  private readonly _snackBar = inject(MatSnackBar);
  public openDialog() {
    const dialogRef = this._dialog.open(YesNoDialog, {
      data: { info: 'Вы уверены, что хотите удалить данный контент?' },
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result === true) {
        this.delete();
      }
    });
  }

  private delete() {
    this._contentState.isProcess.set(true);
    this._contentState.delete().pipe(
      take(1),
      finalize(() => this._contentState.isProcess.set(false))
    ).subscribe({
      next: () => {
        this._snackBar.open("Удалено!", 'Скрыть', {
          duration: 5000,
          horizontalPosition: 'right',
          verticalPosition: 'bottom',
          panelClass: ['primary-snackbar'],
        });
        this._router.navigate(['../../'], { relativeTo: this._route }).then();
      },
      error: (err) => {
        console.log(err);
        this._snackBar.open(`Ошибка удаления: ${err.error.result}`, 'Скрыть', {
          duration: 5000,
          horizontalPosition: 'right',
          verticalPosition: 'bottom',
          panelClass: ['error-snackbar'],
        });
      },
    });
  }
}
