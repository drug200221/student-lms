import { Routes } from '@angular/router';
import { Test } from './components/test/test';

export const AdminRoutes: Routes = [
  {
    path: 'courses/:courseId',
    children: [
      { path: '', component: Test },
    ],
  },
];
