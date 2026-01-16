import { Component, inject } from '@angular/core';
import { MatIconButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatToolbarModule } from '@angular/material/toolbar';
import { CourseState } from '../../../states/course-state';
import { SidenavService } from '../sidenav/sidenav.service';

@Component({
  imports: [
    MatToolbarModule,
    MatIconButton,
    MatIcon,
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
