import { Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: '',
    component: ,
    children: [
      {
        path: 'admin',
        children: [
          // { path: 'courses/:courseId/contents/:contentId/edit', component: EditContentComponent },
          // { path: 'courses/:courseId/contents', component: SubSidebarComponent, children: [] },
        ],
      },

      // { path: 'courses/:courseId/contents', component: SubSidebarComponent, children: [] },
    ],
  },
];
