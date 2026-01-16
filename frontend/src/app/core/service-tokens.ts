import { InjectionToken } from '@angular/core';
import { ContentService } from '../features/user/services/content.service';
import { CourseService } from '../features/user/services/course.service';

export const COURSE_SERVICE_TOKEN = new InjectionToken<CourseService>('CourseService');
export const CONTENT_SERVICE_TOKEN = new InjectionToken<ContentService>('ContentService');
