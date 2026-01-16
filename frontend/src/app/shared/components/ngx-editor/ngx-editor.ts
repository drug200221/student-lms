import {
  Component,
  inject,
  Injector,
  signal,
  ViewEncapsulation,
  OnInit,
  OnDestroy
} from '@angular/core';
import {
  ControlValueAccessor,
  FormControl,
  FormsModule,
  NG_VALUE_ACCESSOR,
  NgControl
} from '@angular/forms';
import { MatError } from '@angular/material/input';
import { NgxEditorModule, Editor, Toolbar } from 'ngx-editor';
import { columnResizing, tableEditing } from 'prosemirror-tables';
import { InsertTableMenu } from './insert-table-menu';
import { customNgxSchema } from './ngx-editor-schema';

@Component({
  encapsulation: ViewEncapsulation.None,
  imports: [
    FormsModule,
    NgxEditorModule,
    MatError,
    InsertTableMenu,
  ],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: NgxEditorComponent,
      multi: true,
    },
  ],
  selector: 'psk-ngx-editor',
  standalone: true,
  styleUrl: './ngx-editor.scss',
  template: `
    <ngx-editor-menu [editor]="editor" [toolbar]="toolbar" [customMenuRef]="customMenu"></ngx-editor-menu>
    <ng-template #customMenu>
      <psk-insert-table-menu [editor]="editor"></psk-insert-table-menu>
    </ng-template>
    <ngx-editor
      [editor]="editor"
      [ngModel]="value()"
      (focusOut)="onFocus()"
      (focusIn)="this.placeholder.set('');"
      (ngModelChange)="onInputChange($event)"
      [class.invalid-editor]="isInvalid"
      [placeholder]="placeholder()"
    >
    </ngx-editor>
    @if (formControl?.errors?.['maxBytes'] && (formControl?.touched || formControl?.dirty)) {
      <mat-error>Размер текста слишком велик</mat-error>
    }
    <ngx-editor-floating-menu [editor]="editor">
      <ngx-editor-menu [editor]="editor" [toolbar]="miniToolbar"></ngx-editor-menu>
    </ngx-editor-floating-menu>
  `,
})
export class NgxEditorComponent implements ControlValueAccessor, OnInit, OnDestroy {
  protected readonly value = signal('');
  protected readonly placeholder = signal('Текст');
  protected readonly isDisabled = signal(false);
  protected readonly isFocused = signal(false);

  private readonly injector = inject(Injector);
  private ngControl: NgControl | null = null;

  public editor!: Editor;
  public ngOnInit() {
    this.editor = new Editor({
      schema: customNgxSchema,
      plugins: [
        columnResizing(),
        tableEditing(),
      ],
    });

    this.ngControl = this.injector.get(NgControl, null);

    setTimeout(() => {
      this.placeholderUpdate();
    }, 50);
  }
  public ngOnDestroy() {
    this.editor.destroy();
  }

  public miniToolbar: Toolbar = [
    ['bold', 'italic', 'underline', 'strike'],
    ['code', 'blockquote'],
    ['link'],
    ['text_color', 'background_color'],
    ['align_left', 'align_center', 'align_right', 'align_justify'],
    ['format_clear'],
  ];

  public toolbar: Toolbar = [
    ['bold', 'italic', 'underline', 'strike'],
    ['code', 'blockquote'],
    ['ordered_list', 'bullet_list'],
    [{ heading: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] }],
    ['link', 'image'],
    ['text_color', 'background_color'],
    ['align_left', 'align_center', 'align_right', 'align_justify'],
    ['horizontal_rule', 'format_clear'],
  ];

  protected get isInvalid() {
    const control = this.ngControl?.control;
    return !!(control && control.invalid && this.isFocused());
  }

  protected get formControl(): FormControl | null {
    return this.ngControl?.control as FormControl | null;
  }

  private onChange: (value: string) => void = () => { /* empty */ };
  private onTouched: () => void = () => { /* empty */ };

  protected onInputChange(val: string) {
    console.log(val);
    this.value.set(val);
    this.onChange(val);
  }

  public writeValue(val: string) {
    this.value.set(val || '');
  }

  public registerOnChange(fn: (value: string) => void) {
    this.onChange = fn;
  }

  public registerOnTouched(fn: () => void) {
    this.onTouched = fn;
  }
  public setDisabledState(isDisabled: boolean): void {
    this.isDisabled.set(isDisabled);
  }

  public onFocus() {
    this.isFocused.set(true);

    if (this.value() === '<p></p>' || this.value() === '') {
      this.placeholderUpdate();
    }
  }

  private placeholderUpdate() {
    this.placeholder.update(() => {
      const control = this.ngControl?.control;
      if (!control) {
        return 'Текст';
      }

      const errors = control.validator?.({} as never);

      return errors && 'required' in errors ? 'Текст*' : 'Текст';
    });
  }
}
