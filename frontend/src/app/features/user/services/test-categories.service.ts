import { Injectable } from '@angular/core';
import { ITestCategory } from '../../../core/models/test-category';
import { AbstractReadOnlyService } from '../../../core/abstract/services/AbstractReadOnlyService';

@Injectable({
  providedIn: 'root',
})
export class TestCategoriesService extends AbstractReadOnlyService<ITestCategory> {
  public override get route(): string {
    return '/courses/categories';
  }
}
