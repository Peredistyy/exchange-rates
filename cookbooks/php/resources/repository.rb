actions :add

def initialize(*args)
    super
    @action = :add
end

attribute :source, :kind_of => String