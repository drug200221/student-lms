import { InjectionToken } from '@angular/core';
import { ContentService } from '../features/user/services/content.service';
import { CourseService } from '../features/user/services/course.service';
import { TestCategoriesService } from '../features/user/services/test-categories.service';

export const COURSE_SERVICE_TOKEN = new InjectionToken<CourseService>('CoursesService');
export const CONTENT_SERVICE_TOKEN = new InjectionToken<ContentService>('ContentsService');
export const TEST_CATEGORIES_SERVICE_TOKEN = new InjectionToken<TestCategoriesService>('TestCategoriesService');
