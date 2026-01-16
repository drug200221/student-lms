import { Injectable } from '@angular/core';
import { AbstractReadOnlyService } from '../../../core/abstract/services/AbstractReadOnlyService';
import { IContent } from '../../../core/models/content';

@Injectable({
  providedIn: 'root',
})
export class ContentService extends AbstractReadOnlyService<IContent> {
  public override get route(): string {
    return '/courses/contents';
  }
}
