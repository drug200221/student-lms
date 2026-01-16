import { NgClass } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatIcon } from '@angular/material/icon';
import { Editor, NgxEditorModule } from 'ngx-editor';
import { Component, Input } from '@angular/core';
import { Fragment, Node as ProsemirrorNode } from 'prosemirror-model';

@Component({
  imports: [
    NgClass,
    NgxEditorModule,
    MatIcon,
    FormsModule,
  ],
  selector: 'psk-insert-table-menu',
  standalone: true,
  styles: ``,
  template: `
    <div class="NgxEditor__Seperator"></div>
    <div class="NgxEditor__MenuItem NgxEditor__MenuItem--Dropdown" [class.NgxEditor__MenuItem--Active]="showPopup">
      <div class="NgxEditor__MenuItem--Icon" (mousedown)="togglePopup($event)" title="Вставить таблицу">
        <mat-icon>border_all</mat-icon>
      </div>
      @if (showPopup) {
        <div class="NgxEditor__Popup">
          <div style="display: flex; gap: 1rem">
            <input type="number" [(ngModel)]="rows" min="1" max="20">
            <span>x</span>
            <input type="number" [(ngModel)]="cols" min="1" max="20">
          </div>
          <button type="button" (click)="insertTable()" [ngClass]="{'NgxEditor--Disabled': isDisabled}" class="NgxEditor__MenuItem--Button">Вставить</button>
        </div>
      }
    </div>
  `,
})
export class InsertTableMenu {
  @Input() public editor!: Editor;
  public isDisabled = false;

  public showPopup = false;
  public rows = 3;
  public cols = 3;

  public togglePopup(e: MouseEvent): void {
    e.preventDefault();
    this.showPopup = !this.showPopup;
  }

  public insertTable(): void {
    const { state, dispatch } = this.editor.view;
    const { schema } = state;

    const { table, table_row, table_cell } = schema.nodes;

    if (!table || !table_row || !table_cell) {
      return;
    }

    const rowsNodes: ProsemirrorNode[] = [];

    for (let r = 0; r < this.rows; r++) {
      const cells: ProsemirrorNode[] = [];

      for (let c = 0; c < this.cols; c++) {
        const cell = table_cell.createAndFill();
        if (cell) {
          cells.push(cell);
        }
      }

      rowsNodes.push(table_row.create(null, Fragment.from(cells)));
    }

    const tableNode = table.create(null, Fragment.from(rowsNodes));
    const tr = state.tr.replaceSelectionWith(tableNode).scrollIntoView();

    if (dispatch) {
      dispatch(tr);
      this.showPopup = false;
      this.editor.view.focus();
    }
  }
}
