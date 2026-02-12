import {
  Component,
  ViewEncapsulation,
  OnInit,
  OnDestroy,
  inject,
  DestroyRef,
  Input,
  ChangeDetectorRef, ChangeDetectionStrategy
} from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import {
  ControlValueAccessor,
  FormsModule,
  NgControl
} from '@angular/forms';
import { MatError } from '@angular/material/input';
import { NgxEditorModule, Editor, toDoc, toHTML } from 'ngx-editor';
import { keymap } from 'prosemirror-keymap';
import { columnResizing, goToNextCell, tableEditing } from 'prosemirror-tables';
import { TableCommand } from './commands/table-command';
import customNgxSchema from './ngx-editor-schema';
import { miniToolbar, toolbar } from './ngx-toolbars';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  selector: 'psk-ngx-editor',
  standalone: true,
  imports: [
    FormsModule,
    NgxEditorModule,
    MatError,
    TableCommand,
  ],
  template: `
    <ngx-editor-menu [editor]="editor" [toolbar]="toolbar" [customMenuRef]="customMenu"></ngx-editor-menu>
    <ng-template #customMenu>
      <div class="NgxEditor__Seperator"></div>
      <psk-table-command class="NgxEditor__Dropdown" [editor]="editor"></psk-table-command>
    </ng-template>
    <ngx-editor
      [editor]="editor"
      [attr.required]="required"
      (focusOut)="onFocus()"
      [class.invalid-editor]="isInvalid"
      [placeholder]="placeholder"
    >
    </ngx-editor>
    @if (formControl?.errors?.['maxBytes'] && (formControl?.touched || formControl?.dirty)) {
      <mat-error>Размер текста слишком велик</mat-error>
    }
    <ngx-editor-floating-menu [editor]="editor">
      <ngx-editor-menu [editor]="editor" [toolbar]="miniToolbar"></ngx-editor-menu>
    </ngx-editor-floating-menu>
  `,
  styleUrl: './ngx-editor.scss',
  encapsulation: ViewEncapsulation.None,
})
export class NgxEditorComponent implements ControlValueAccessor, OnInit, OnDestroy {
  @Input() public required = false;
  @Input() public placeholder = 'Текст';

  public editor!: Editor;
  public readonly ngControl = inject(NgControl, { self: true, optional: true });

  private readonly cdr = inject(ChangeDetectorRef);
  private readonly destroyRef = inject(DestroyRef);

  private pendingValue: string | Record<string, unknown> | null = null;
  private isSelfChange = false;

  protected readonly miniToolbar = miniToolbar;
  protected readonly toolbar = toolbar;

  constructor() {
    if (this.ngControl) {
      this.ngControl.valueAccessor = this;
    }
  }

  public ngOnInit(): void {
    this.editor = new Editor({
      schema: customNgxSchema,
      nodeViews: {},
      plugins: [
        columnResizing(),
        tableEditing(),
        keymap({
          "Tab": goToNextCell(1),
          "Shift-Tab": goToNextCell(-1),
        }),
      ],
    });

    this.editor.valueChanges
      .pipe(
        takeUntilDestroyed(this.destroyRef))
      .subscribe((value) => {
        if (this.isSelfChange) {
          return;
        }

        this.onChange(toHTML(value, this.editor.schema));
      });

    if (this.pendingValue) {
      this.writeValue(this.pendingValue);
      this.pendingValue = null;
    }
  }

  public writeValue(val: string | Record<string, unknown> | null): void {
    if (!this.editor?.view) {
      this.pendingValue = val;
      return;
    }

    this.isSelfChange = true;
    const schema = this.editor.schema;
    const doc = typeof val === 'string' ? toDoc(val, schema) : val;

    this.editor.setContent(doc || toDoc('', schema));

    setTimeout(() => {
      this.isSelfChange = false;
      this.cdr.markForCheck();
    });
  }

  public registerOnChange(fn: (value: string | Record<string, unknown>) => void): void {
    this.onChange = fn;
  }

  public registerOnTouched(fn: () => void): void {
    this.onTouched = fn;
  }

  public get isInvalid(): boolean {
    const control = this.ngControl?.control;
    return !!(control && control.invalid && (control.touched || control.dirty));
  }

  public get formControl() {
    return this.ngControl?.control;
  }

  public onFocus(): void {
    this.onTouched();
    this.cdr.markForCheck();
  }

  public ngOnDestroy(): void {
    this.editor?.destroy();
  }

  private onChange: (value: string | Record<string, unknown>) => void = () => { /* empty */ };
  private onTouched: () => void = () => { /* empty */ };
}
