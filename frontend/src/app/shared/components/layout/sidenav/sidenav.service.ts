import { computed, Injectable, signal } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { debounceTime, fromEvent, map, startWith } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class SidenavService {
  private _windowWidth = toSignal(
    fromEvent(window, 'resize').pipe(
      debounceTime(100),
      map(() => window.innerWidth),
      startWith(window.innerWidth)
    ),
    { initialValue: window.innerWidth }
  );

  public isMediumScreen = computed(() => this._windowWidth() >= 768);
  public leftMode = computed(() => this.isMediumScreen() ? 'side' : 'over');
  public isLeftOpened = signal(this.isMediumScreen());

  public toggleLeft() {
    this.isLeftOpened.update(v => !v);
  }
}
