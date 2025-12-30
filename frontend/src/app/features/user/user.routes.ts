import { Routes } from '@angular/router';

export const UserRoutes: Routes = [
  {
    path: '',
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
