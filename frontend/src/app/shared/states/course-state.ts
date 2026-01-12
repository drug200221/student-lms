import { computed, inject, Injectable } from '@angular/core';
import { rxResource } from '@angular/core/rxjs-interop';
import { ActivatedRoute } from '@angular/router';
import { EMPTY, Observable } from 'rxjs';
import { IApiResponse } from '../../core/interfaces/api-response';
import { ICourse } from '../../core/models/course';
import { SERVICE_TOKEN } from '../../core/service-token';

@Injectable()
export class CourseState {
  private route = inject(ActivatedRoute);

  private dataService = inject(SERVICE_TOKEN);

  public readonly baseRoute = this.dataService.route;

  public readonly id = computed(() => {
    const id = this.route.snapshot.firstChild?.params['courseId'];
    return id ? parseInt(id) : undefined;
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
