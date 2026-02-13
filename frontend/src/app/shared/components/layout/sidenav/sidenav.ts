import { AfterViewInit, Component, computed, effect, inject, ViewEncapsulation } from '@angular/core';
import { MatButton, MatIconButton, MatMiniFabButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatMenuModule } from '@angular/material/menu';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatTreeModule, MatTreeNestedDataSource } from '@angular/material/tree';
import { RouterLink, RouterOutlet } from '@angular/router';
import { CourseState } from '../../../states/course-state';
import { IMenuItem } from '../layout';
import { SidenavService } from './sidenav.service';
import { NgClass } from '@angular/common';
import { ContentState } from '../../../states/content-state';
import { PortalModule } from '@angular/cdk/portal';
import { PortalService } from '../../../../core/portals/portal.service';

@Component({
  encapsulation: ViewEncapsulation.None,
  imports: [
    MatSidenavModule,
    MatTreeModule,
    MatMenuModule,
    RouterLink,
    MatIcon,
    RouterOutlet,
    MatIconButton,
    MatButton,
    NgClass,
    MatMiniFabButton,
    PortalModule,
  ],
  selector: 'psk-sidenav',
  standalone: true,
  styleUrl: './sidenav.scss',
  templateUrl: './sidenav.html',
})
export class Sidenav implements AfterViewInit {
  public sidenavService = inject(SidenavService);
  public portalService = inject(PortalService);
  public courseState = inject(CourseState);
  public contentState = inject(ContentState);

  public hasRightContent = computed(() => !!this.portalService.activePortal());

  protected dataSource = new MatTreeNestedDataSource<IMenuItem>();

  constructor() {
    effect(() => {
      this.dataSource.data = this.menuStructure();
    });
  }

  public ngAfterViewInit() {
    setTimeout(() => {
      window.dispatchEvent(new Event('resize'));
    }, 100);
  }

  public readonly menuStructure = computed(() => {
    const response = this.courseState.data.value();

    if (!response?.result) {
      this.dataSource.data = [];
      return [];
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

    return menu;
  });

  private setRoutes(item: IMenuItem, baseRoute: string) {
    if (item.id !== -1) {
      item.route = `${baseRoute}/contents/${item.id}`;
    }

    item.children?.forEach(child => this.setRoutes(child, baseRoute));
  }

  protected childrenAccessor = (node: IMenuItem) => node.children ?? [];
  protected hasChild = (_: number, node: IMenuItem) => !!node.children && node.children.length > 0;
}
