import {Component, OnInit} from '@angular/core';

import {User} from "../../../models/user";
import {UserService} from "../../../services/user.service";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.sass'],
  providers: [UserService]
})
export class RegisterComponent implements OnInit {

  public page_title: string
  public user: User
  public result: {
    status: string
    code: number
    message: string
    errors: {
      name: [string]
      surname: [string]
      email: [string]
      password: [string]
    }
  }

  constructor(
    private _userService: UserService
  ) {
    this.page_title = 'Regístrate'
    this.user = new User(1, '', '', 'ROLE_USER', '', '', '', '')
    this.result = {
      status: null,
      code: null,
      message: null,
      errors: {
        name: null,
        surname: null,
        email: null,
        password: null
      }
    }
  }

  ngOnInit(): void {
  }

  onSubmit(form) {
    this._userService.signUp(this.user).subscribe(
      response => {
        this.result = response

        if (this.result.status) {
          form.reset()
        }
      },
      error => {
        this.result = error.error
        console.log(this.result.errors.email[0])
      }
    )

  }

}
