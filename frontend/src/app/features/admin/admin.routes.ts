import { Routes } from '@angular/router';
import { AddContent } from './components/content/add-content';
import { AContent } from './components/content/a-content';
import { EditContent } from './components/content/edit-content';
import { Test } from './components/test/test';

export const AdminRoutes: Routes = [
  {
    path: 'courses/:courseId',
    children: [
      { path: 'tests', component: Test },
      { path: 'files', component: Test },
      { path: 'contents/:contentId/edit', component: EditContent },
      { path: 'contents/:contentId', component: AContent },
      { path: 'contents', component: AddContent },
    ],
  },
];
