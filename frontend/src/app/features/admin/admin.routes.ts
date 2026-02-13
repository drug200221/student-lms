import { Routes } from '@angular/router';
import { AddContent } from './components/content/add-content';
import { AContent } from './components/content/a-content';
import { EditContent } from './components/content/edit-content';
import { Test } from './components/test/test';
import { ListTCategories } from './components/test/categories/list-t-categories';
import { AddTCategory } from './components/test/categories/add-t-category';

export const AdminRoutes: Routes = [
  {
    path: 'courses/:courseId',
    children: [
      { path: 'tests/categories/add', component: AddTCategory },
      { path: 'tests/categories', component: ListTCategories },
      { path: 'tests', component: Test },
      { path: 'files', component: Test },
      { path: 'contents/add', component: AddContent },
      { path: 'contents/:contentId/edit', component: EditContent },
      { path: 'contents/:contentId', component: AContent },
    ],
  },
];
