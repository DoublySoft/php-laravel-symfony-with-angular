import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs";
import {Global} from "./global";

@Injectable({
  providedIn: 'root'
})
export class UserService {

  public url: string;

  constructor(
    private _http: HttpClient
  ) {
    this.url = Global.url;
  }

  signUp(user): Observable<any>{

    let json = JSON.stringify(user)
    let params = 'json=' + json
    let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8')

    return this._http.post(this.url + 'register', params, {headers: headers})
  }

  logIn(user, getToken = null): Observable<any>{

    if (getToken != null) {
      user.getToken = true
    }

    let json = JSON.stringify(user)
    let params = 'json=' + json
    let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8')

    return this._http.post(this.url + 'register', params, {headers: headers})
  }
}
