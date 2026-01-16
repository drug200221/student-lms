import { Component } from '@angular/core';
import { MatCardModule } from '@angular/material/card';

@Component({
  imports: [
    MatCardModule,
  ],
  selector: 'psk-test',
  standalone: true,
  styleUrl: './test.scss',
  templateUrl: './test.html',
})
export class Test {

}
