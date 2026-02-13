import { Injectable } from '@angular/core';
import { AbstractCrudService } from '../../../core/abstract/services/AbstractCrudService';
import { ITestCategory } from '../../../core/models/test-category';

@Injectable({
  providedIn: 'root',
})
export class TestCategoriesService extends AbstractCrudService<ITestCategory> {
  public override get route(): string {
    return '/admin/courses/categories';
  }
}
