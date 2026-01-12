import { Component, computed, effect, inject, OnInit } from '@angular/core';
import { MatButton, MatIconButton } from '@angular/material/button';
import { MatCard } from '@angular/material/card';
import { MatExpansionPanel, MatExpansionPanelTitle } from '@angular/material/expansion';
import { MatIcon } from '@angular/material/icon';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { MatListItem, MatNavList } from '@angular/material/list';
import { MatMenuItem, MatMenuModule } from '@angular/material/menu';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatTreeModule, MatTreeNestedDataSource } from '@angular/material/tree';
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router';
import { SERVICE_TOKEN } from '../../../../core/service-token';
import { AuthService } from '../../../../core/services/auth.service';
import { CourseService as AdminCourseService } from '../../../../features/admin/services/course.service';
import { CourseService as UserCourseService } from '../../../../features/user/services/course.service';
import { CourseState } from '../../../states/course-state';
import { IMenuItem } from '../layout';
import { SidenavService } from './sidenav.service';

@Component({
  imports: [
    MatSidenavModule,
    MatTreeModule,
    MatMenuModule,
    RouterLink,
    MatIcon,
    RouterOutlet,
    MatMenuItem,
    MatCard,
    MatButton,
    MatFormField,
    MatInput,
    MatLabel,
    RouterLinkActive,
    MatIconButton,
    MatNavList,
    MatListItem,
    MatExpansionPanelTitle,
    MatExpansionPanel,
  ],
  providers: [
    CourseState,
    { provide: SERVICE_TOKEN,
      useFactory: () => {
        const authService = inject(AuthService);
        return authService.isAdmin()
          ? inject(AdminCourseService)
          : inject(UserCourseService);
      },
    },
  ],
  selector: 'psk-sidenav',
  standalone: true,
  styleUrl: './sidenav.scss',
  templateUrl: './sidenav.html',
})
export class Sidenav implements OnInit {
  public sidenavService = inject(SidenavService);
  public courseState = inject(CourseState);

  protected dataSource = new MatTreeNestedDataSource<IMenuItem>();

  constructor() {
    effect(() => {
      this.menuStructure();
    });
  }

  public readonly menuStructure = computed(() => {
    const response = this.courseState.data.value();

    if (!response?.result) {
      this.dataSource.data = [];
      return;
    }

    const course = response.result;
    const contents = course ? structuredClone(course.contents) : [];
    const baseRoute = `${this.courseState.baseRoute}/${course.id}`;

    const menu: IMenuItem[] = [
      { id: -1, title: 'Содержание', icon: 'book',        route: baseRoute,           children: contents },
      { id: -1, title: 'Тесты',      icon: 'quiz',        route: `${baseRoute}/tests` },
      { id: -1, title: 'Файлы',      icon: 'description', route: `${baseRoute}/files` },
    ];

    menu.forEach(item => this.setRoutes(item, baseRoute));

    this.dataSource.data = menu;
  });

  private setRoutes(item: IMenuItem, baseRoute: string) {
    if (item.id !== -1) {
      item.route = `${baseRoute}/contents/${item.id}`;
    }
    console.log(item.route);
    item.children?.forEach(child => this.setRoutes(child, baseRoute));
  }

  protected childrenAccessor = (node: IMenuItem) => node.children ?? [];
  protected hasChild = (_: number, node: IMenuItem) => !!node.children && node.children.length > 0;
}
