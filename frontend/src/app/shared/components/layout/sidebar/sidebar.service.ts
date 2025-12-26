import { Injectable, signal } from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { debounceTime, fromEvent } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class SidebarService {
  public isLargeScreen = signal(window.innerWidth >= 920);
  public isOpened =  signal(window.innerWidth >= 920);
  public isToggled = signal(false);

  constructor() {
    fromEvent(window, 'resize')
      .pipe(
        debounceTime(100),
        takeUntilDestroyed()
      )
      .subscribe(() => {
        const width = window.innerWidth;
        const isLarge = width >= 920;

        this.isLargeScreen.set(isLarge);

        if (!this.isToggled()) {
          this.isOpened.set(isLarge);
        }
      });
  }

  public toggle() {
    this.isToggled.set(true);
    this.isOpened.update(v => !v);
  }
}
