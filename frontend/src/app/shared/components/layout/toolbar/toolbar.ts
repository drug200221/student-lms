import { Component, inject } from '@angular/core';
import { MatIconButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatToolbarModule } from '@angular/material/toolbar';
import { CourseState } from '../../../states/course-state';
import { SidenavService } from '../sidenav/sidenav.service';
import { MatProgressBar } from '@angular/material/progress-bar';
import { ContentState } from '../../../states/content-state';

@Component({
  imports: [
    MatToolbarModule,
    MatIconButton,
    MatIcon,
    MatProgressBar,
  ],
  selector: 'psk-toolbar',
  standalone: true,
  styles: ``,
  templateUrl: './toolbar.html',
})
export class Toolbar {
  public sidenavService = inject(SidenavService);
  public courseState = inject(CourseState);
  public contentState = inject(ContentState);
}
