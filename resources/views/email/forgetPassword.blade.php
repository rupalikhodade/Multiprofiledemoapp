<section>
    <div>
        <h4>You are receiving this email because we received a password reset request for your account.</h4>        
        You can reset password from bellow link:
        <a href="{{ route('password.reset', $token.'?email='.$email) }}">Reset Password</a>
        <h5>This password reset link will expire in 60 minutes.</h5>
        <h5>If you did not request a password reset, no further action is required.</h5>
    </div>
</section>