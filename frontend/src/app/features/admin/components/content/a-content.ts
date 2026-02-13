import { Component } from '@angular/core';
import { Content } from '../../../../shared/components/content/content';
import { AContentSidenav } from './a-content-sidenav';
import { CdkPortal } from '@angular/cdk/portal';
import { PortalDirective } from '../../../../core/portals/portal.directive';

@Component({
  imports: [
    Content,
    AContentSidenav,
    CdkPortal,
    PortalDirective,
  ],
  selector: 'psk-a-content',
  standalone: true,
  styles: ``,
  template: `
      <psk-content></psk-content>
      <ng-template cdkPortal pskPortal>
        <psk-a-content-sidenav />
      </ng-template>
  `,
})
export class AContent {
}
