import { computed, inject, Injectable, signal } from '@angular/core';
import { rxResource, toSignal } from '@angular/core/rxjs-interop';
import { ActivatedRouteSnapshot, NavigationEnd, Router } from '@angular/router';
import { distinctUntilChanged, EMPTY, filter, map, startWith, tap, throwError } from 'rxjs';
import { IContent, ICourseContent } from '../../core/models/content';
import { CONTENT_SERVICE_TOKEN } from '../../core/service-tokens';
import { ContentsService } from '../../features/admin/services/contents.service';
import { CourseState } from './course-state';

@Injectable({
  providedIn: 'root',
})
export class ContentState {
  private _courseState = inject(CourseState);
  private _router = inject(Router);
  private _dataService = inject(CONTENT_SERVICE_TOKEN);

  public isProcess = signal(false);
  public readonly baseRoute = this._dataService.route;

  private readonly idFromParam = toSignal(
    this._router.events.pipe(
      filter((e): e is NavigationEnd => e instanceof NavigationEnd),
      startWith(null),
      map(() => {
        const findParam = (route: ActivatedRouteSnapshot): string | undefined => {
          if (route.params['contentId']) {
            return route.params['contentId'];
          }
          return route.firstChild ? findParam(route.firstChild) : undefined;
        };

        return findParam(this._router.routerState.snapshot.root);
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
        return EMPTY;
      }
      return this._dataService.getById(id);
    },
  });

  public save(changes: Partial<IContent>) {
    const service = this._dataService as ContentsService;
    const currentId = this.id();

    const request = currentId
      ? service.update(currentId, changes, true)
      : service.create(changes, true);

    return request.pipe(
      tap(res => {
        if (!res.success || !res.result) {
          return;
        }

        const updatedContent = res.result;
        this.data.reload();

        this._courseState.data.update(current => {
          if (!current?.result) {
            return current;
          }

          const contents = JSON.parse(JSON.stringify(current.result.contents));

          const upsertRecursive = (items: ICourseContent[]): boolean => {
            const index = items.findIndex(item => String(item.id) === String(updatedContent.id));

            if (index > -1) {
              items[index] = {
                ...items[index],
                ...updatedContent,
                children: items[index].children || [],
              };
              items.sort((a: ICourseContent, b: ICourseContent) => (a.treeOrder ?? 0) - (b.treeOrder ?? 0));
              return true;
            }

            for (const item of items) {
              if (item.children?.length) {
                if (upsertRecursive(item.children)) {
                  return true;
                }
              }
            }

            const parent = items.find(item => String(item.id) === String(updatedContent.parentId));
            if (parent) {
              if (!parent.children) {
                parent.children = [];
              }
              if (!parent.children.some(c => String(c.id) === String(updatedContent.id))) {
                parent.children.push({ ...updatedContent, children: [] });
                parent.children.sort((a: ICourseContent, b: ICourseContent) => (a.treeOrder ?? 0) - (b.treeOrder ?? 0));
              }
              return true;
            }

            return false;
          };

          const processed = upsertRecursive(contents);

          if (!processed && !updatedContent.parentId) {
            contents.push({ ...updatedContent, children: [] });
            contents.sort((a: ICourseContent, b: ICourseContent) => (a.treeOrder ?? 0) - (b.treeOrder ?? 0));
          }

          return {
            ...current,
            result: { ...current.result, contents },
          };
        });
      })
    );
  }

  public delete() {
    const service = this._dataService;
    const currentId = this.id();

    if (!currentId) {
      return EMPTY;
    }

    if ('delete' in service && typeof service.delete === 'function') {
      return (service as ContentsService).delete(currentId, true).pipe(
        tap(() => {
          this.data.reload();
          this._courseState.data.reload();
        })
      );
    }

    return throwError(() => new Error('Нет прав'));
  }
}
