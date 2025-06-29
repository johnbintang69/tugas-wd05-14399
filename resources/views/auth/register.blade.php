<!-- resources/views/auth/register.blade.php -->
@extends('layout.auth')

@section('title', 'Register')

@push('styles')
<style>
  .toggle-password {
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .toggle-password:hover {
    background-color: #e9ecef;
  }
  .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }
  .custom-control-input:checked ~ .custom-control-label::before {
    border-color: #007bff;
    background-color: #007bff;
  }
  .custom-control-label::before {
    border: 1px solid #adb5bd;
  }
</style>
@endpush
@section('body-class', 'register-page')

@section('content')
<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Syarat dan Ketentuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>1. Persetujuan Umum</h5>
        <p>Dengan mendaftar dan menggunakan layanan kami, Anda menyetujui untuk terikat dengan syarat dan ketentuan yang berlaku.</p>
        
        <h5>2. Data Pribadi</h5>
        <p>Kami akan melindungi data pribadi Anda sesuai dengan kebijakan privasi kami. Data yang Anda berikan akan digunakan untuk keperluan layanan kesehatan.</p>
        
        <h5>3. Kewajiban Pengguna</h5>
        <p>Anda bertanggung jawab untuk menjaga kerahasiaan akun dan kata sandi Anda. Segala aktivitas yang terjadi di bawah akun Anda menjadi tanggung jawab Anda.</p>
        
        <h5>4. Pembaruan Syarat</h5>
        <p>Kami berhak untuk memperbarui syarat dan ketentuan ini kapan saja. Perubahan akan diberitahukan melalui email atau pemberitahuan di situs kami.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Saya Mengerti</button>
      </div>
    </div>
  </div>
</div>
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ url('/') }}" class="h1"><b>Poli</b>klinik</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Daftar akun baru</p>

      <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <div class="input-group">
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                   placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required autocomplete="name" autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            @error('nama')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="no_ktp">Nomor KTP</label>
          <div class="input-group">
            <input type="text" name="no_ktp" id="no_ktp" class="form-control @error('no_ktp') is-invalid @enderror" 
                   placeholder="Masukkan 16 digit nomor KTP" value="{{ old('no_ktp') }}" required autocomplete="off" maxlength="16">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-card"></span>
              </div>
            </div>
            @error('no_ktp')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <small class="text-muted">Masukkan 16 digit nomor KTP</small>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-group">
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="contoh@email.com" value="{{ old('email') }}" required autocomplete="email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="no_hp">Nomor HP</label>
          <div class="input-group">
            <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                   placeholder="Contoh: 81234567890" value="{{ old('no_hp') }}" required autocomplete="tel">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
            @error('no_hp')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <small class="text-muted">Tanpa awalan 0 (contoh: 81234567890)</small>
        </div>
        <div class="form-group">
          <div class="input-group">
            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                      placeholder="Alamat Lengkap" required autocomplete="street-address" 
                      rows="2">{{ old('alamat') }}</textarea>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-home"></span>
              </div>
            </div>
            @error('alamat')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group">
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Buat password minimal 8 karakter" required autocomplete="new-password" minlength="8">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary toggle-password" type="button" 
                      data-toggle="password" data-target="password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <small class="text-muted">Minimal 8 karakter</small>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password</label>
          <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                   placeholder="Ketik ulang password Anda" required autocomplete="new-password">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary toggle-password" type="button" 
                      data-toggle="password" data-target="password_confirmation">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            @error('password_confirmation')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div class="form-group mb-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input @error('terms') is-invalid @enderror" 
                   id="agreeTerms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
            <label class="custom-control-label" for="agreeTerms">
              Saya setuju dengan <a href="#" data-toggle="modal" data-target="#termsModal">syarat dan ketentuan</a> yang berlaku
            </label>
            @error('terms')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>

        <div class="form-group mb-0">
          <button type="submit" class="btn btn-primary btn-block btn-lg">
            <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
          </button>
        </div>
        
        <!-- Hidden role field - default to 'pasien' -->
        <input type="hidden" name="role" value="pasien">
      </form>

      <a href="{{ route('login') }}" class="text-center">Saya sudah punya akun</a>
    </div>
    <!-- /.card-body -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
@endsection

@push('scripts')
<script>
  // Toggle password visibility
  function togglePassword(button) {
    const targetId = button.getAttribute('data-target');
    const input = document.getElementById(targetId);
    const icon = button.querySelector('i');
    
    if (!input) return;
    
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  // Initialize password toggle buttons
  document.addEventListener('DOMContentLoaded', function() {
    // Password toggle
    document.addEventListener('click', function(e) {
      if (e.target.closest('.toggle-password')) {
        togglePassword(e.target.closest('.toggle-password'));
      }
    });

    // Format nomor HP
    const phoneInput = document.getElementById('no_hp');
    if (phoneInput) {
      phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
          value = value.substring(1);
        }
        e.target.value = value;
      });
    }

    // Format nomor KTP
    const ktpInput = document.getElementById('no_ktp');
    if (ktpInput) {
      ktpInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 16);
      });
    }
  });
</script>
@endpush