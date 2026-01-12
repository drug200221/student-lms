import { InjectionToken } from '@angular/core';
import { AbstractReadOnlyService } from './abstract/services/AbstractReadOnlyService';

export const SERVICE_TOKEN = new InjectionToken<AbstractReadOnlyService<never>>('SERVICE_TOKEN');
