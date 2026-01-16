import { computed, inject, Injectable } from '@angular/core';
import { rxResource, toSignal } from '@angular/core/rxjs-interop';
import { ActivatedRouteSnapshot, NavigationEnd, Router } from '@angular/router';
import { distinctUntilChanged, EMPTY, filter, map, Observable, startWith } from 'rxjs';
import { IApiResponse } from '../../core/interfaces/api-response';
import { ICourse } from '../../core/models/course';
import { COURSE_SERVICE_TOKEN } from '../../core/service-tokens';

@Injectable({
  providedIn: 'root',
})
export class CourseState {
  private router = inject(Router);

  private dataService = inject(COURSE_SERVICE_TOKEN);

  public readonly baseRoute = this.dataService.route;

  private readonly idFromParam = toSignal(
    this.router.events.pipe(
      filter((e): e is NavigationEnd => e instanceof NavigationEnd),
      startWith(null),
      map(() => {
        const findParam = (route: ActivatedRouteSnapshot): string | undefined => {
          if (route.params['courseId']) {
            return route.params['courseId'];
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
      if (id === undefined || id === null) {
        return EMPTY as Observable<IApiResponse<ICourse>>;
      }

      return this.dataService.getById(id);
    },
    defaultValue: undefined,
  });
}
