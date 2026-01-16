import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { toHTML } from 'ngx-editor';
import { customNgxSchema } from '../components/ngx-editor/ngx-editor-schema';

/**
 * Максимальный размер данных в байтах для типа TEXT в MySQL.
 *
 * @description
 * Лимит составляет 65,535 байт (64 КБ).
 *
 * @see {@link https://dev.mysql.com/doc/refman/9.5/en/storage-requirements.html MySQL Storage Requirements}
 */
export const MYSQL_TEXT_MAX_BYTES = 65535;
export function maxBytesValidator(max = MYSQL_TEXT_MAX_BYTES): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const doc = control.value;
    if (!doc) {
      return null;
    }

    const htmlString = toHTML(doc, customNgxSchema);
    const bytes = new Blob([htmlString]).size;

    return bytes > max ? { maxBytes: { actual: bytes, max } } : null;
  };
}
