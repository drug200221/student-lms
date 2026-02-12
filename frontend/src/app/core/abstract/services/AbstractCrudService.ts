import { Observable, tap, catchError, throwError, Subject, map } from 'rxjs';
import { IApiResponse } from '../../interfaces/api-response';
import { AbstractReadOnlyService } from './AbstractReadOnlyService';

export abstract class AbstractCrudService<T extends { id?: string | number }> extends AbstractReadOnlyService<T> {
  public readonly itemUpdated$ = new Subject<T>();
  public readonly itemDeleted$ = new Subject<string | number>();

  protected upsertItem(item: T): void {
    const list = this._data();
    const idx = list.findIndex(i => i.id !== undefined && i.id === item.id);
    const next = idx === -1 ? [...list, item] : [...list.slice(0, idx), item, ...list.slice(idx + 1)];

    this._data.set(next);
    this.itemUpdated$.next(item);
  }

  protected removeById(id: string | number): void {
    this._data.set(this._data().filter(i => i.id !== id));
    this.itemDeleted$.next(id);
  }

  public create(payload: Partial<T>, optimistic = false): Observable<IApiResponse<T>> {
    if (optimistic && (payload as T).id !== undefined) {
      this.upsertItem(payload as T);
    }

    return this.http.post<IApiResponse<T>>(`${this.fullUrl}/`, payload).pipe(
      tap(res => {
        if (res.success && res.result) {
          this.upsertItem(res.result);
        }
      }),
      catchError(err => throwError(() => err))
    );
  }

  public update(id: string | number, changes: Partial<T>, optimistic = false): Observable<IApiResponse<T>> {
    const url = `${this.fullUrl}/${id}`;
    let backup: T | undefined;

    if (optimistic) {
      const currentItem = this._data().find(i => i.id === id);
      if (currentItem) {
        backup = { ...currentItem };
        this.upsertItem({ ...currentItem, ...changes } as T);
      }
    }

    return this.http.put<IApiResponse<T> | string>(url, changes, { responseType: 'text' as 'json' }).pipe(
      map(res => {
        try {
          return typeof res === 'string' ? JSON.parse(res) : res;
        } catch (e) {
          const errorMessage = e instanceof Error ? e.message : 'Unknown error';
          return {
            success: false,
            message: `Ошибка парсинга: ${errorMessage}`,
          };
        }
      }),
      tap(res => {
        if (res.success && res.result) {
          this.upsertItem(res.result);
        }
      }),
      catchError(err => {
        if (optimistic && backup) {
          this.upsertItem(backup);
        }
        return throwError(() => err);
      })
    );
  }

  public delete(id: string | number, optimistic = false): Observable<IApiResponse<void>> {
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
        return throwError(() => err);
      })
    );
  }
}
