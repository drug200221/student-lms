
import { Component, inject, OnInit } from '@angular/core';
import { MatButton } from '@angular/material/button';
import { MatCard } from '@angular/material/card';
import { MatIcon } from '@angular/material/icon';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { MatMenu, MatMenuItem } from '@angular/material/menu';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatTreeModule, MatTreeNestedDataSource } from '@angular/material/tree';
import { ActivatedRoute, RouterLink, RouterOutlet } from '@angular/router';
import { ICourse } from '../../../../core/models/course';
import { CourseService } from '../../../../features/user/services/course.service';
import { IMenuItem } from '../layout';
import { SidenavService } from './sidenav.service';

@Component({
  imports: [
    MatSidenavModule,
    MatTreeModule,
    MatMenu,
    RouterLink,
    MatIcon,
    RouterOutlet,
    MatMenuItem,
    MatCard,
    MatButton,
    MatFormField,
    MatInput,
    MatLabel,
  ],
  selector: 'psk-sidenav',
  standalone: true,
  styleUrl: './sidenav.scss',
  templateUrl: './sidenav.html',
})
export class Sidenav implements OnInit {
  public sidenavService = inject(SidenavService);
  public courseService = inject(CourseService);

  protected dataSource = new MatTreeNestedDataSource<IMenuItem>();

  private route = inject(ActivatedRoute);

  public ngOnInit() {
    const courseId = this.route.snapshot.firstChild?.params['courseId'];

    this.courseService.getById(courseId).subscribe((res) => {
      const courseData = res.result ? res.result : null;

      this.refreshMenu(courseData);
    });
  }

  private refreshMenu(course: ICourse | null) {
    if (!course) {
      return;
    }

    const routeBase = `${this.courseService.route}/${course.id}`;

    const menu: IMenuItem[] = [
      {
        id: 0, title: 'Содержание', icon: 'book',
        route: routeBase,
        children: course.contents,
      },
      { id: -1, title: 'Тесты', icon: 'check-box', route: `${routeBase}/tests` },
    ];

    console.log(menu);
    console.log(course.contents);

    if (menu[0].children) {
      menu[0].children.forEach(child => this.setLinks(child, routeBase));
    }

    this.dataSource.data = menu;
  }

  private setLinks(item: IMenuItem, route: string) {
    item.route = `/${route}/contents/${item.id}`;
    item.children?.forEach(child => this.setLinks(child, route));
  }

  protected childrenAccessor = (node: IMenuItem) => node.children ?? [];

  protected hasChild = (_: number, node: IMenuItem) => !!node.children && node.children.length > 0;
}
