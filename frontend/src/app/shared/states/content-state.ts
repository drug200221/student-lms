import { computed, inject, Injectable } from '@angular/core';
import { rxResource, toSignal } from '@angular/core/rxjs-interop';
import { ActivatedRouteSnapshot, NavigationEnd, Router } from '@angular/router';
import { distinctUntilChanged, EMPTY, filter, map, Observable, startWith, tap, throwError } from 'rxjs';
import { IApiResponse } from '../../core/interfaces/api-response';
import { IContent } from '../../core/models/content';
import { CONTENT_SERVICE_TOKEN } from '../../core/service-tokens';
import { ContentService } from '../../features/admin/services/content.service';

@Injectable({
  providedIn: 'root',
})
export class ContentState {
  private router = inject(Router);
  private dataService = inject(CONTENT_SERVICE_TOKEN);

  public readonly baseRoute = this.dataService.route;

  private readonly idFromParam = toSignal(
    this.router.events.pipe(
      filter((e): e is NavigationEnd => e instanceof NavigationEnd),
      startWith(null),
      map(() => {
        const findParam = (route: ActivatedRouteSnapshot): string | undefined => {
          if (route.params['contentId']) {
            return route.params['contentId'];
          }
          return route.firstChild ? findParam(route.firstChild) : undefined;
        };

        return findParam(this.router.routerState.snapshot.root);
      }),
      distinctUntilChanged()
    )
  );

  public readonly id = computed(() => {
    const id = this.idFromParam();
    return id ? parseInt(id, 10) : undefined;
  });

  public readonly data = rxResource({
    params: () => this.id(),
    stream: ({ params: id }) => {
      if (!id) {
        return EMPTY as Observable<IApiResponse<IContent>>;
      }
      console.log(this.baseRoute);
      return this.dataService.getById(id);
    },
  });

  public save(changes: Partial<object>): Observable<IApiResponse<IContent>> {
    const service = this.dataService;
    const currentId = this.id();

    if (currentId) {
      if ('update' in service && typeof service.update === 'function') {
        return (service as ContentService).update(currentId, changes, true).pipe(
          tap(res => res.success && this.data.reload())
        );
      }
    } else {
      if ('create' in service && typeof service.create === 'function') {
        return (service as ContentService).create(changes, true).pipe(
          tap(res => res.success && this.data.reload())
        );
      }
    }

    return throwError(() => new Error('Нет прав'));
  }

  public delete(): Observable<IApiResponse<void>> {
    const service = this.dataService;
    const currentId = this.id();

    if (!currentId) {
      return EMPTY;
    }

    if ('delete' in service && typeof service.delete === 'function') {
      return (service as ContentService).delete(currentId, true).pipe(
        tap((res: IApiResponse<void>) => {
          if (res.success) {
            this.router.navigate(['../']).then();
          }
        })
      );
    }

    return throwError(() => new Error('Нет прав'));
  }
}
