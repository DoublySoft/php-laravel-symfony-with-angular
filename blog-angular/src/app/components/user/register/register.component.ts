import { Component, OnInit } from '@angular/core';
import {User} from "../../../models/user";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.sass']
})
export class RegisterComponent implements OnInit {

  public page_title: string;
  public user: User;

  constructor() {
    this.page_title = 'Regístrate';
    this.user = new User(1,'','','ROLE_USER','','','','');
  }

  ngOnInit(): void {
    console.log('Componente de registro lanzado!');
  }

  onSubmit() {
    console.log(this.user)
  }

}
