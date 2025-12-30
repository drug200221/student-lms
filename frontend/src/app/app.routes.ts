import { Routes } from '@angular/router';
import { AdminLayout } from './core/layouts/admin-layout';
import { MainLayout } from './core/layouts/main-layout';

export const routes: Routes = [
  {
    path: 'admin',
    component: AdminLayout,
    canActivate: [],
    loadChildren: () => import('./features/admin/admin.routes').then(m => m.AdminRoutes),
  },

  {
    path: '',
    component: MainLayout,
    canActivate: [],
    loadChildren: () => import('./features/user/user.routes').then(m => m.UserRoutes),
  },

  { path: '**', redirectTo: '' },
];
