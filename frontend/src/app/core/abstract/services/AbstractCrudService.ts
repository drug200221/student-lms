import { Observable, tap, catchError } from 'rxjs';
import { IApiResponse } from '../../interfaces/api-response';
import { AbstractReadOnlyService } from './AbstractReadOnlyService';

export abstract class AbstractCrudService<T extends { id?: string | number }> extends AbstractReadOnlyService<T> {
  protected upsertItem(item: T): void {
    const list = this._data();
    const idx = list.findIndex(i => i.id !== undefined && i.id === item.id);
    const next = idx === -1 ? [...list, item] : [...list.slice(0, idx), item, ...list.slice(idx + 1)];
    this._data.set(next);
  }

  protected removeById(id: string | number): void {
    this._data.set(this._data().filter(i => i.id !== id));
  }

  public create(payload: Partial<T>, optimistic = false): Observable<IApiResponse<T>> {
    this._error.set(null);

    if (optimistic && (payload as T).id !== undefined) {
      this.upsertItem(payload as T);
    }

    return this.http.post<IApiResponse<T>>(this.fullUrl, payload).pipe(
      tap(res => {
        if (res.success && res.result) {
          this.upsertItem(res.result);
        }
      }),
      catchError(err => this.handleError(err))
    );
  }
  public update(id: string | number, changes: Partial<T>, optimistic = false): Observable<IApiResponse<T>> {
    this._error.set(null);
    const url = `${this.fullUrl}/${id}`;
    let backup: T | undefined;

    if (optimistic) {
      const currentItem = this._data().find(i => i.id === id);
      if (currentItem) {
        backup = { ...currentItem };
        this.upsertItem({ ...currentItem, ...changes } as T);
      }
    }

    return this.http.patch<IApiResponse<T>>(url, changes).pipe(
      tap(res => {
        if (res.success && res.result) {
          this.upsertItem(res.result);
        }
      }),
      catchError(err => {
        if (optimistic && backup) {
          this.upsertItem(backup);
        }

        return this.handleError(err);
      })
    );
  }

  public delete(id: string | number, optimistic = false): Observable<IApiResponse<void>> {
    this._error.set(null);
    const url = `${this.fullUrl}/${id}`;
    let backup: T | undefined;

    if (optimistic) {
      backup = this._data().find(i => i.id === id);
      this.removeById(id);
    }

    return this.http.delete<IApiResponse<void>>(url).pipe(
      tap(res => {
        if (!res.success && optimistic && backup) {
          this.upsertItem(backup);
        }
      }),
      catchError(err => {
        if (optimistic && backup) {
          this.upsertItem(backup);
        }

        return this.handleError(err);
      })
    );
  }
  public snapshot(): T[] {
    return this._data();
  }
}
