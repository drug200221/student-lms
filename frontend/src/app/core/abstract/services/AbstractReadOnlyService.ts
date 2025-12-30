import { inject, signal } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { catchError, finalize, Observable, tap, throwError } from 'rxjs';
import { environment } from '../../../../environments/environment';
import { IApiResponse } from '../../interfaces/api-response';

export abstract class AbstractReadOnlyService<T> {
  protected http = inject(HttpClient);

  protected readonly _data = signal<T[]>([]);
  protected readonly _current = signal<T | null>(null);
  protected readonly _loading = signal<boolean>(false);
  protected readonly _error = signal<string | null>(null);

  public get items() { return this._data.asReadonly(); }
  public get current() { return this._current.asReadonly(); }
  public get isLoading() { return this._loading.asReadonly(); }
  public get error() { return this._error.asReadonly(); }
  public get count() { return this._data().length; }

  public abstract get route(): string;

  protected get fullUrl(): string {
    return `${environment.ROOT_BACKEND_URL}${environment.BASE_API_ROUTE}${this.route}`;
  }

  public loadAll(): Observable<IApiResponse<T[]>> {
    this.prepareRequest();

    return this.http.get<IApiResponse<T[]>>(this.fullUrl).pipe(
      tap((res) => {
        if (res.success && res.result) {
          this._data.set(res.result);
        }
      }),
      catchError((err) => this.handleError(err)),
      finalize(() => this._loading.set(false))
    );
  }
  public getById(id: string | number): Observable<IApiResponse<T>> {
    this.prepareRequest();

    return this.http.get<IApiResponse<T>>(`${this.fullUrl}/${id}`).pipe(
      tap((res) => {
        if (res.success && res.result) {
          this._current.set(res.result);
        }
      }),
      catchError((err) => this.handleError(err)),
      finalize(() => this._loading.set(false))
    );
  }

  private prepareRequest(): void {
    this._loading.set(true);
    this._error.set(null);
  }

  protected handleError(err: unknown): Observable<never> {
    let message = 'Unknown Error';

    if (err instanceof HttpErrorResponse) {
      message = err.error?.message || err.message;
    } else if (err instanceof Error) {
      message = err.message;
    } else if (typeof err === 'string') {
      message = err;
    }

    this._error.set(message);

    return throwError(() => err);
  }
}
