import { Component, inject } from '@angular/core';
import {
  MatCell,
  MatCellDef,
  MatColumnDef,
  MatHeaderCell,
  MatHeaderCellDef, MatHeaderRow,
  MatHeaderRowDef, MatRow, MatRowDef,
  MatTable, MatTableDataSource
} from '@angular/material/table';
import { MatIcon } from '@angular/material/icon';
import { MatFabButton, MatIconButton } from '@angular/material/button';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatDialog } from '@angular/material/dialog';
import { YesNoDialog } from '../../../../../shared/components/yes-no-dialog/yes-no-dialog';
import { ITestCategory } from '../../../../../core/models/test-category';
import { TestCategoriesState } from '../../../../../shared/states/test-categories-state';

@Component({
  imports: [
    MatTable,
    MatIcon,
    MatFabButton,
    RouterLink,
    MatColumnDef,
    MatHeaderCell,
    MatCell,
    MatHeaderCellDef,
    MatCellDef,
    MatIconButton,
    MatHeaderRowDef,
    MatRowDef,
    MatHeaderRow,
    MatRow,
  ],
  selector: 'psk-list-t-categories',
  standalone: true,
  styles: ``,
  template: `
    <button matFab extended routerLink="add" style="margin-bottom: 8px">
      <mat-icon>add</mat-icon>
      Добавить категорию
    </button>

    <table [dataSource]="dataSource" class="mat-elevation-z1" mat-table>
      <ng-container matColumnDef="title">
        <th *matHeaderCellDef mat-header-cell>Название</th>
        <td *matCellDef="let element" mat-cell>{{element.title}}</td>
      </ng-container>

      <ng-container matColumnDef="action-edit">
        <th *matHeaderCellDef mat-header-cell></th>
        <td *matCellDef="let element" class="cell-control" mat-cell>
          <button [routerLink]='[element.id, "edit"]' matIconButton>
            <mat-icon color="primary">edit</mat-icon>
          </button>
        </td>
      </ng-container>

      <ng-container matColumnDef="action-delete">
        <th *matHeaderCellDef mat-header-cell></th>
        <td *matCellDef="let element" class="cell-control" mat-cell>
          <button (click)="openDialog(element.id)" matIconButton>
            <mat-icon color="warn">remove</mat-icon>
          </button>
        </td>
      </ng-container>

      <tr *matHeaderRowDef="['title', 'action-edit', 'action-delete']" mat-header-row></tr>
      <tr *matRowDef="let row; columns: ['title', 'action-edit', 'action-delete']" mat-row></tr>
    </table>
  `,
})
export class ListTCategories {
  protected readonly testCategoriesState = inject(TestCategoriesState);
  protected dataSource = new MatTableDataSource<ITestCategory>();
  private _route = inject(ActivatedRoute);
  private readonly _dialog = inject(MatDialog);
  private readonly _snackBar = inject(MatSnackBar);

  public openDialog(categoryId: number): void {
    const info = 'Вы уверены, что хотите удалить данную категорию?';
    const dialogRef = this._dialog.open(YesNoDialog, {
      data: { info },
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result === true) {
        this.delete(categoryId);
      }
    });
  }

  private delete(categoryId: number) {
    console.log(categoryId);
  }
}
