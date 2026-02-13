import { inject, Injectable } from '@angular/core';
import { rxResource } from '@angular/core/rxjs-interop';
import { EMPTY, tap } from 'rxjs';
import { TEST_CATEGORIES_SERVICE_TOKEN } from '../../core/service-tokens';
import { CourseState } from './course-state';
import { ITestCategory } from '../../core/models/test-category';
import { TestCategoriesService } from '../../features/admin/services/test-categories.service';

@Injectable({ providedIn: 'root' })
export class TestCategoriesState {
  private courseState = inject(CourseState);
  private readonly _dataService = inject(TEST_CATEGORIES_SERVICE_TOKEN);

  public readonly data = rxResource({
    params: () => ({ courseId: this.courseState.id() }),
    stream: ({ params }) => {
      if (!params.courseId) {
        return EMPTY;
      }
      return this._dataService.loadAll(`course-id=${params.courseId}`);
    },
  });

  public save(id: number | null, changes: Partial<ITestCategory>) {
    const service = this._dataService as TestCategoriesService;

    const request = id
      ? service.update(id, changes)
      : service.create(changes);

    return request.pipe(
      tap((res) => {
        if (res.success && res.result) {
          const updatedItem = res.result;

          this.data.update(state => {
            if (!state) {
              return state;
            }

            const list = state.result ?? [];
            const index = list.findIndex(item => item.id === updatedItem.id);

            const newList = index !== -1
              ? list.map(item => item.id === updatedItem.id ? updatedItem : item)
              : [...list, updatedItem];

            return {
              ...state,
              result: newList,
            };
          });
        }
      })
    );
  }

  public delete(id: number) {
    const service = this._dataService as TestCategoriesService;

    return service.delete(id).pipe(
      tap((res) => {
        if (res.success) {
          this.data.update(state => {
            if (!state?.result) {
              return state;
            }

            return {
              ...state,
              result: state.result.filter(item => item.id !== id),
            };
          });
        }
      })
    );
  }
}
