import { inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { catchError, Observable, tap, throwError } from 'rxjs';
import { environment } from '../../../../environments/environment';
import { IApiResponse } from '../../interfaces/api-response';

export abstract class AbstractReadOnlyService<T> {
  protected http = inject(HttpClient);

  protected readonly _data = signal<T[]>([]);
  public get items() { return this._data.asReadonly(); }

  public abstract get route(): string;

  protected get fullUrl(): string {
    return `${environment.ROOT_BACKEND_URL}${environment.BASE_API_ROUTE}${this.route}`;
  }

  public loadAll(): Observable<IApiResponse<T[]>> {
    return this.http.get<IApiResponse<T[]>>(this.fullUrl).pipe(
      tap((res) => {
        if (res.success && res.result) {
          this._data.set(res.result);
        }
      }),
      catchError((err) => throwError(() => err))
    );
  }

  public getById(id: string | number): Observable<IApiResponse<T>> {
    return this.http.get<IApiResponse<T>>(`${this.fullUrl}/${id}`).pipe(
      catchError((err) => throwError(() => err))
    );
  }
}
