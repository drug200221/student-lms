import { Routes } from '@angular/router';
import { AContent } from './components/content/index/a-content';
import { EditContent } from './components/content/edit/edit-content';
import { Test } from './components/test/test';

export const AdminRoutes: Routes = [
  {
    path: 'courses/:courseId',
    children: [
      { path: 'tests', component: Test },
      { path: 'files', component: Test },
      { path: 'contents/:contentId/edit', component: EditContent },
      { path: 'contents/:contentId', component: AContent },
      // { path: 'contents', component: AContent },
    ],
  },
];
