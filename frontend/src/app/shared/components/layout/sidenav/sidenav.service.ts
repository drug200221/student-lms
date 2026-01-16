import { computed, effect, Injectable, signal } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { debounceTime, fromEvent, map, startWith } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class SidenavService {
  private windowWidth = toSignal(
    fromEvent(window, 'resize').pipe(
      debounceTime(100),
      map(() => window.innerWidth),
      startWith(window.innerWidth)
    ),
    { initialValue: window.innerWidth }
  );

  public isMediumScreen = computed(() => this.windowWidth() >= 768);
  public leftMode = computed(() => this.isMediumScreen() ? 'side' : 'over');
  public isLeftOpened = signal(false);

  constructor() {
    effect(() => {
      this.isLeftOpened.set(false);
      setTimeout(() => {
        this.isLeftOpened.set(this.isMediumScreen());
      });
    });
  }

  public toggleLeft() {
    this.isLeftOpened.update(v => !v);
  }
}
