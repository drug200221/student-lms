import { Injectable, signal } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class SidebarService {
  public readonly isOpened =  signal<boolean | null>(null);
  public isToggled = signal<boolean>(false);

  public toggle() {
    this.isToggled.set(true);
    this.isOpened.update(v => !v);
  }
}
