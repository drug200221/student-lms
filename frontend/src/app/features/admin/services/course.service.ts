import { Injectable } from '@angular/core';
import { AbstractCrudService } from '../../../core/abstract/services/AbstractCrudService';
import { ICourse } from '../../../core/models/course';

@Injectable({
  providedIn: 'root',
})
export class CourseService extends AbstractCrudService<ICourse> {
  public override get route(): string {
    return '/admin/courses';
  }
}
