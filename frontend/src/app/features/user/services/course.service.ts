import { Injectable } from '@angular/core';
import { AbstractReadOnlyService } from '../../../core/abstract/services/AbstractReadOnlyService';
import { ICourse } from '../../../core/models/course';

@Injectable({
  providedIn: 'root',
})
export class CourseService extends AbstractReadOnlyService<ICourse> {
  public override get route(): string {
    return '/courses';
  }
}
