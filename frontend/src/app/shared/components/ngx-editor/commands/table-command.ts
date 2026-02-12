import { Component, Input, ViewEncapsulation } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatIconModule } from '@angular/material/icon';
import { MatDivider } from '@angular/material/list';
import { MatMenu, MatMenuItem, MatMenuTrigger } from '@angular/material/menu';
import { Editor } from 'ngx-editor';
import { EditorState, Transaction } from 'prosemirror-state';
import {
  addColumnAfter,
  addColumnBefore,
  addRowAfter,
  addRowBefore,
  deleteColumn,
  deleteRow,
  deleteTable,
  toggleHeaderColumn,
  toggleHeaderCell,
  toggleHeaderRow
} from 'prosemirror-tables';
import { Fragment } from 'prosemirror-model';

@Component({
  encapsulation: ViewEncapsulation.None,
  imports: [CommonModule, FormsModule, MatIconModule, MatDivider, MatMenu, MatMenuTrigger, MatMenuItem],
  selector: 'psk-table-command',
  standalone: true,
  styleUrl: '../ngx-editor.scss',
  template: `
    <button type="button" [matMenuTriggerFor]="tableMenu" class="NgxEditor__Dropdown--Text default-color no-shape">
      Таблица
    </button>

    <mat-menu #tableMenu="matMenu">
      @if (!canExec(deleteTable)) {
        <button type="button" mat-menu-item (click)="execInsertTable()">
          <span>Вставить таблицу (3x3)</span>
        </button>
      } @else {
        <button type="button" mat-menu-item (click)="exec(addColumnBefore)">+ Столбец левее</button>
        <button type="button" mat-menu-item (click)="exec(addColumnAfter)">+ Столбец правее</button>
        <button type="button" class="error" mat-menu-item (click)="exec(deleteColumn)">Удалить столбец</button>

        <mat-divider></mat-divider>

        <button type="button" mat-menu-item (click)="exec(addRowBefore)">+ Строка выше</button>
        <button type="button" mat-menu-item (click)="exec(addRowAfter)">+ Строка ниже</button>
        <button type="button" class="error" mat-menu-item (click)="exec(deleteRow)">Удалить строку</button>

        <mat-divider></mat-divider>
        <p style="text-align: center; font-size: .9rem">Заголовок/Обычный</p>
        <button type="button" mat-menu-item (click)="exec(toggleHeaderRow)">Строка: З/О</button>
        <button type="button" mat-menu-item (click)="exec(toggleHeaderColumn)">Столбец: З/О</button>
        <button type="button" mat-menu-item (click)="exec(toggleHeaderCell)">Ячейка: З/О</button>

        <mat-divider></mat-divider>

        <button type="button" class="error" mat-menu-item (click)="exec(deleteTable)">Удалить всю таблицу</button>
      }
    </mat-menu>
  `,
})
export class TableCommand {
  @Input() public editor!: Editor;

  public showPopup = false;
  public rows = 3;
  public cols = 3;

  public togglePopup(event: MouseEvent) {
    event.preventDefault();
    this.showPopup = !this.showPopup;
  }

  public canExec(command: Command): boolean {
    return command(this.editor.view.state);
  }

  public execInsertTable() {
    const { state, dispatch } = this.editor.view;
    const schema = this.editor.schema;

    const cellType = schema.nodes['table_cell'];
    const rowType = schema.nodes['table_row'];
    const tableType = schema.nodes['table'];

    const cell = cellType.createAndFill();
    const trs = [];

    for (let i = 0; i < this.rows; i++) {
      const cells = [];
      for (let j = 0; j < this.cols; j++) {
        cells.push(cell!);
      }
      trs.push(rowType.create(null, Fragment.from(cells)));
    }

    const table = tableType.create(null, Fragment.from(trs));

    dispatch(state.tr.replaceSelectionWith(table).scrollIntoView());
    this.showPopup = false;
  }

  public exec(command: Command) {
    const { state, dispatch } = this.editor.view;
    command(state, dispatch);
    this.showPopup = false;
    this.editor.view.focus();
  }

  protected readonly addColumnBefore = addColumnBefore;
  protected readonly addColumnAfter = addColumnAfter;
  protected readonly deleteColumn = deleteColumn;
  protected readonly addRowBefore = addRowBefore;
  protected readonly addRowAfter = addRowAfter;
  protected readonly deleteRow = deleteRow;
  protected readonly deleteTable = deleteTable;
  protected readonly toggleHeaderColumn = toggleHeaderColumn;
  protected readonly toggleHeaderRow = toggleHeaderRow;
  protected readonly toggleHeaderCell = toggleHeaderCell;
}

type Command = (state: EditorState, dispatch?: (tr: Transaction) => void) => boolean;
