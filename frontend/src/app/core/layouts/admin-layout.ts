import { Component } from '@angular/core';
import { Sidenav } from '../../shared/components/layout/sidenav/sidenav';
import { Toolbar } from '../../shared/components/layout/toolbar/toolbar';

@Component({
  imports: [
    Sidenav,
    Toolbar,
  ],
  selector: 'psk-admin-layout',
  standalone: true,
  styles: `
    main {
      display: flex;
      flex-direction: column;
      height: 100vh;

      psk-sidenav {
        flex: 1;
        display: block;
      }
    }
  `,
  template: `
    <main>
      <psk-toolbar></psk-toolbar>
      <psk-sidenav></psk-sidenav>
    </main>
  `,
})
export class AdminLayout {

}
