import { NgClass } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { Card } from 'primeng/card';
import { SidebarComponent } from './sidebar/sidebar.component';
import { SidebarService } from './sidebar/sidebar.service';
import { TopbarComponent } from './toolbar/topbar.component';

@Component({
  imports: [
    TopbarComponent,
    RouterOutlet,
    SidebarComponent,
    Card,
    NgClass,
  ],
  selector: 'psk-layout',
  standalone: true,
  template: `
    <div class="grid">
      <psk-sidebar [ngClass]="sidebarService.isOpened() && window.innerWidth > 900 ? 'w-8rem' : 'w-1rem'"></psk-sidebar>
      <div class="col">
        <psk-toolbar></psk-toolbar>
        <div class="layout-main">
          <p-card class="m-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto corporis cupiditate exercitationem expedita illo impedit magnam nam possimus, provident quaerat qui, quos recusandae reiciendis repudiandae sequi, suscipit temporibus voluptate voluptatem.
          </p-card>
          <p-card class="m-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto corporis cupiditate exercitationem expedita illo impedit magnam nam possimus, provident quaerat qui, quos recusandae reiciendis repudiandae sequi, suscipit temporibus voluptate voluptatem.
          </p-card>
          <p-card class="m-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto corporis cupiditate exercitationem expedita illo impedit magnam nam possimus, provident quaerat qui, quos recusandae reiciendis repudiandae sequi, suscipit temporibus voluptate voluptatem.
          </p-card>
          <p-card class="m-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto corporis cupiditate exercitationem expedita illo impedit magnam nam possimus, provident quaerat qui, quos recusandae reiciendis repudiandae sequi, suscipit temporibus voluptate voluptatem.
          </p-card>
          <p-card class="m-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto corporis cupiditate exercitationem expedita illo impedit magnam nam possimus, provident quaerat qui, quos recusandae reiciendis repudiandae sequi, suscipit temporibus voluptate voluptatem.
          </p-card>
          <router-outlet></router-outlet>
        </div>
      </div>
    </div>`,
})
export class LayoutComponent {
  public sidebarService = inject(SidebarService);
  protected readonly window = window;
}
