import { Component, inject } from '@angular/core';
import { MatButton, MatIconButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatToolbarModule } from '@angular/material/toolbar';
import { SERVICE_TOKEN } from '../../../../core/service-token';
import { CourseService } from '../../../../features/user/services/course.service';
import { CourseState } from '../../../states/course-state';
import { SidenavService } from '../sidenav/sidenav.service';

@Component({
  imports: [
    MatToolbarModule,
    MatIconButton,
    MatIcon,
    MatButton,
  ],
  providers: [
    CourseState,
    { provide: SERVICE_TOKEN, useExisting: CourseService },
  ],
  selector: 'psk-toolbar',
  standalone: true,
  styleUrl: './toolbar.scss',
  templateUrl: './toolbar.html',
})
export class Toolbar {
  public sidenavService = inject(SidenavService);
  public courseState = inject(CourseState);
}
