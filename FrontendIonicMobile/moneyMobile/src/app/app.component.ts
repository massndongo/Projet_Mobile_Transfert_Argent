import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {

  public appPages = [
    { title: 'Home', url: '/home', icon: 'home' },
    { title: 'Transactions', url: '/transactions', icon: 'time' },
    { title: 'Commissions', url: '/commissions', icon: 'cash' },
    { title: 'Calculateur', url: '/calculateur', icon: 'calculator' },
  ];

  constructor() {}

}
