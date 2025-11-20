class FormValidator {
  static validateName(name) {
    return name
      && typeof name === "string"
      && name.length >= 3;
  }

  static validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }
  
  static validatePassword(password) {
    return password
      && typeof password === "string"
      && password.length > 8;
  }
}
