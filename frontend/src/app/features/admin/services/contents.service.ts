import { Injectable } from '@angular/core';
import { AbstractCrudService } from '../../../core/abstract/services/AbstractCrudService';
import { IContent } from '../../../core/models/content';

@Injectable({
  providedIn: 'root',
})
export class ContentsService extends AbstractCrudService<IContent> {
  public override get route(): string {
    return '/admin/courses/contents';
  }
}
